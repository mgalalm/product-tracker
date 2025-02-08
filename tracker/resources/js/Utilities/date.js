import { formatDistance, parseISO } from "date-fns";

const relativeDate = (date) =>
    formatDistance(new Date(date), new Date(), { addSuffix: true });


const fullMonth = (date) => {
    const parsedDate = parseISO(date);
    const month = parsedDate.toLocaleString("default", { month: "long" });
    const day = parsedDate.getDate();
    const year = parsedDate.getFullYear();
    return `${month} ${day}, ${year}`;
};

export { relativeDate, fullMonth };
