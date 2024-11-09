export const paginationMeta = (options, total) => {
  const start = (options.page - 1) * options.itemsPerPage + 1;
  const end = Math.min(options.page * options.itemsPerPage, total);

  return {
    start: total === 0 ? 0 : start,
    end: end,
    total: total,
  }; // `Showing ${total === 0 ? 0 : start} to ${end} of ${total} entries`
};
