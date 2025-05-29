export const correctSvgList = (mode, configs) => {
  let list = configs && configs.svgs ? configs.svgs[mode] : [];
  if (list.length > 0) {
    list = list.map((v) => {
      return "/images/" + configs.folder + "/" + v + ".svg";
    });
  }
  return list;
};

export const isNumberFinderExam = (patientExam) => {
  return patientExam.exam_id === 4;
};

export const isSvgExam = (patientExam) => {
  return [1, 2, 3, 5, 6].includes(patientExam.exam_id);
};
