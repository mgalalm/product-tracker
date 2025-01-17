import { formatDistance, parseISO } from "date-fns";

const relativeDate = (date) =>
    formatDistance(new Date(date), new Date(), { addSuffix: true });

export { relativeDate };
