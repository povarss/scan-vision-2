export const correctSvgList = (mode, configs) => {
  console.log(configs,'configs');
  let list = configs ? configs.svgs[mode] : [];
  if (list.length > 0) {
    list = list.map((v) => {
      return "/images/" + configs.folder + "/" + v + ".svg";
    });
  }
  return list;
};
