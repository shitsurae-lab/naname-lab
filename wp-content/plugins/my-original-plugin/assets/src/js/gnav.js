const gnav = () => {
  // console.log('gnav.jsが正常に読み込まれています');
  //JavaScriptの.addEventListener()でhoverした時、メニューに'active'を付与されたタイミングでanimationが発火するようにする(PC時のみ - mixinで設定済)
  //①https://dubdesign.net/javascript/hover-tooltip/
  //②https://gxy-life.com/2PC/PC/PC20211211.html
  const gnav_item_a = document.querySelectorAll('.c-gnav__a');
  gnav_item_a.forEach((elem) => {
    elem.addEventListener('mouseover', () => {
      if (elem.classList.contains('active')) {
        elem.classList.remove('active');
        // console.log('activeが含まれます');
      } else {
        elem.classList.add('active');
        // console.log('activeが含まれません');
      }
    });
  });
};
export default gnav;
