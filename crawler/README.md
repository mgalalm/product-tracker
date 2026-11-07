# Crawler

A FastAPI service that scrapes retailer product pages and returns parsed product data
(name, price, availability) as JSON.

## API

```
GET /{site}/{pid}
```

- `site` — a retailer key configured in `retailer/config.py` (e.g. `amazon-ie`, `currys`, `tesco`).
  An unknown site returns `404` listing the configured retailers.
- `pid` — the retailer's product id, appended to that retailer's `base_url` to build the
  page URL to fetch.

Requires a bearer token (`Authorization: Bearer <AUTH_TOKEN>`) matching the `AUTH_TOKEN`
setting.

Example:

```
GET /currys/10234567
Authorization: Bearer <AUTH_TOKEN>
```

returns:

```json
{
  "name": "...",
  "price": "...",
  "availability": "..."
}
```

## Driver abstraction

Fetching a page is delegated to a "driver", so the same parsing logic works whether the
page is fetched from a local headless browser or through a remote proxy.

- **`BaseDriver`** (`drivers/base_driver.py`) — abstract interface every driver implements:
  `get_content()` (fetch and return the raw page HTML) and `close()`. `parse()` is provided
  by the base class and runs `ParserService` over the selectors configured for the site.
- **`DriverFactory`** (`drivers/driver_factory.py`) — given a `site` and `pid`, looks up the
  retailer's config (base URL, selectors, `driver_mode`) and instantiates the right driver
  class via `DRIVER_MAPPING`. An unrecognised `driver_mode` raises a `ValueError`.
- **`SeleniumDriver`** (`drivers/selenium_driver.py`) — `driver_mode: "local"`. Drives a
  headless Chrome instance directly; used for sites that don't need a proxy.
- **`BrightDataDriver`** (`drivers/brightdata_driver.py`) — `driver_mode: "remote"`. Fetches
  pages through the Bright Data proxy (`BD_PROXY_URL`) using `httpx`. TLS verification is on
  by default; see [Environment variables](#environment-variables) to configure it.

`ParserService` (`retailer/services/parser_service.py`) takes the raw HTML from the driver
and a dict of CSS selectors, and extracts `{name, price, availability}` with BeautifulSoup.

## Adding a new retailer

No code changes needed — add an entry to the `retailers` dict in `retailer/config.py`:

```python
"my-retailer": {
    "base_url": "https://www.my-retailer.ie/p/",
    "selectors": {
        "name": "h1",
        "price": "span.price",
        "availability": "span.availability"
    },
    "driver_mode": "local"  # or "remote"
}
```

`base_url` is concatenated with `pid` to build the page URL. `driver_mode` must be one of
the keys in `DriverFactory.DRIVER_MAPPING` ("local" or "remote").

## Adding a new driver

1. Implement `BaseDriver` (`get_content()` and `close()`) in `drivers/`.
2. Register it in `DriverFactory.DRIVER_MAPPING` (`drivers/driver_factory.py`):

```python
DRIVER_MAPPING = {
    "local": {"module": "drivers.selenium_driver", "class_name": "SeleniumDriver"},
    "remote": {"module": "drivers.brightdata_driver", "class_name": "BrightDataDriver"},
    "my-mode": {"module": "drivers.my_driver", "class_name": "MyDriver"},
}
```

3. Reference the new mode as a retailer's `driver_mode` in `retailer/config.py`.

## Environment variables

Set these in `crawler/.env` (gitignored) or the process environment:

| Variable | Required | Description |
|---|---|---|
| `BD_PROXY_URL` | yes | Bright Data proxy URL used by `BrightDataDriver`, e.g. `http://user:pass@host:port`. |
| `AUTH_TOKEN` | no (has an insecure default — set this in any real deployment) | Bearer token required on every request. |
| `BD_VERIFY_SSL` | no (default `true`) | Set to `false` to skip TLS verification on the proxy connection. Not recommended; logs a warning when disabled. |
| `BD_CA_CERT_PATH` | no | Path to a custom CA bundle to trust when verifying the proxy's TLS certificate, instead of the default `certifi` bundle. |

## Running

Locally:

```bash
pip install -r requirements.txt
uvicorn retailer.main:app --reload --log-config log_config.yaml
```

With Docker:

```bash
docker build -t crawler .
docker run -p 8000:8000 --env-file .env crawler
```

Tests:

```bash
pip install -r requirements-dev.txt
pytest
```
