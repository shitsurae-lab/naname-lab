//GSAP
import { gsap } from 'gsap';

function timelineGSAP() {
  //① bodyタグを取得
  const bodyElement = document.querySelector('body');
  //②カーテン要素
  const curtain = document.createElement('div');
  curtain.setAttribute('class', 'curtain');
  //③loader
  const loader = document.createElement('div');
  loader.setAttribute('class', 'p-loader');
  //④spinner
  const spinner = document.createElement('div');
  spinner.setAttribute('class', 'p-spinner');
  //⑤オープニングアニメーション
  const opening = document.createElement('div');
  opening.setAttribute('class', 'p-opening');
  //要素をDOM
  bodyElement.prepend(loader);
  loader.prepend(spinner);
  loader.after(opening);
  opening.after(curtain);
  //END 要素をDOMに

  //- START 関数
  const js_opening = () => {
    opening.classList.add('loaded');
    console.log('js_openingが読み込まれました');
  };
  const js_curtain = () => {
    //元はjs_secondary
    curtain.style.opacity = '0';
    curtain.style.visibility = hidden;
    curtain.style.display = none;
    console.log('カーテンが見えなくなります');
  };
  const js_pageOn = () => {
    //元はjs_primary
    bodyElement.classList.add('pageOn');
    console.log("body要素に'pageOn'");
  };
  const js_add_loaded = () => loader.classList.add('loaded');
  //- END 関数

  document.addEventListener('DOMContentLoaded', () => {
    let flg = null;
    let check_access = function () {
      // ★sessionStorageの値を判定
      if (sessionStorage.getItem('access_flg')) {
        // 2回目以降
        flg = 1;
      } else {
        // 1回目
        sessionStorage.setItem('access_flg', true);
        flg = 0;
      }
      return flg;
    };
    let $i = check_access();
    const tl = gsap.timeline();
    if ($i == 0) {
      // tl.to();
    } else {
    }
  });
}

export default timelineGSAP;
