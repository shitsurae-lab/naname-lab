//document.addEventListener('DOMContentLoaded'..がフックになるJS-----
const openingAnime = () => {
  //① bodyタグを取得
  const bodyElement = document.querySelector('body');

  //②spinnnerエリア
  const opening = document.createElement('div');
  opening.setAttribute('class', 'p-spinner__wrapper');
  //②-1 spinnerを作成
  const spinner = document.createElement('div');
  spinner.setAttribute('class', 'p-spinner');
  //③loadingアニメーション
  const loader = document.createElement('div');
  loader.setAttribute('class', 'p-loader');
  const loaderContent = document.createElement('div');
  loaderContent.setAttribute('class', 'p-loader__container');
  loader.prepend(loaderContent);
  const loaderText = document.createElement('p');
  loaderText.setAttribute('class', 'p-loader__text u-uppercase');
  loaderContent.prepend(loaderText);
  loaderText.innerText = 'Let me introduce\nmyself';

  //④カーテン要素の作成
  const curtainElement = document.createElement('div');
  curtainElement.setAttribute('class', 'p-curtain');

  //①body要素内の先頭にp-loader
  bodyElement.prepend(opening);
  //②p-opening要素内の先頭にp-spinner
  opening.prepend(spinner);
  //③ p-openingの後ろにp-loader
  opening.after(loader);
  //④ p-loaderの後ろにカーテン要素
  loader.after(curtainElement);

  const js_opening = () => {
    opening.classList.add('loaded');
    //console.log('①js_openingが読み込まれたよ!');
  };

  const js_loader = () => {
    loader.classList.add('loaded');
    //console.log('②js_loaderが読み込まれたよ!');
  };

  const js_curtain = () => {
    curtainElement.classList.add('loaded', 'change');
    //console.log('③js_curtainが読み込まれたのよ!');
  };

  const js_pageOn = () => {
    document.body.classList.add('pageOn');
    curtainElement.classList.remove('change');
    //console.log('④ js_pageOnが読み込まれたぜ');
  };

  /*コールバック関数 */

  // ページ更新時、最初だけローディングアニメーション
  //①beforeunloadとunloadは「更新検知」
  //window.addEventListener('load', () => { //②これは画像まで読み込まれてからの意味
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
    if ($i == 0) {
      //1回目アクセスの処理
      setTimeout(() => {
        setTimeout(() => {
          setTimeout(() => {
            setTimeout(() => {
              js_pageOn();
            }, 1000); //curtaniAnime 800ms(duration)の少しあとにjs_pageOnが動くようにする
            js_curtain();
          }, 2000); //loaderAnime 2000ms(duration)にあわせてずらした
          js_loader();
        }, 1200); //spinnerAnime 1200ms(duration)にあわせてずらした
        js_opening();
      }, 0);
    } else {
      // 2回目アクセスの処理
      setTimeout(() => {
        setTimeout(() => {
          setTimeout(() => {
            setTimeout(() => {
              js_pageOn();
            }, 1000); //curtaniAnime 800ms(duration)の少しあとにjs_pageOnが動くようにする
            js_curtain();
          }, 2000); //loaderAnime 2000ms(duration)にあわせてずらした
          js_loader();
        }, 0);
        opening.remove();
      }, 0);
    }
  });
};

export default openingAnime;
