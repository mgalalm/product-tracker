from retailer.services import parser_service

def get_parser_service(site: str, pid: str):
    return parser_service.ParserService(site, pid)