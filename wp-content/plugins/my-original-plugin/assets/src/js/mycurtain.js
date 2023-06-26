const mycurtain = () => {
  // bodyタグを取得
  const bodyElement = document.querySelector('body');
  // body直下にオーバーレイの要素を作成
  const curtainElement = document.createElement('div');
  curtainElement.setAttribute('class', 'leftCurtainbg');
  //loaderを作成
  const loader = document.createElement('div');
  loader.setAttribute('class', 'p-loader');
  //spinnerを作成
  const spinner = document.createElement('div');
  spinner.setAttribute('class', 'p-spinner');
  //①body要素内の先頭にloader
  bodyElement.prepend(loader);
  //②loader要素内の先頭にspinner
  loader.prepend(spinner);
  //③ ①で配置されたloaderの後ろにカーテン要素
  loader.after(curtainElement);

  //START: カーテンをdisplay:none; & Bodyに"pageOn"を付加(関数に入れない)
  function js_secondary() {
      curtainElement.style.display = 'none';
      console.log('②番目だよ');
      console.log('カーテン要素をnoneに');
  }

  function js_primary() {
    document.body.classList.add('pageOn');
    console.log('①番目だよ');
    console.log('bodyにpageOn');
  }
  // setTimeout(() => {
  //   setTimeout(() => {
  //     //カーテン要素をdisplay:noneに
  //     curtainElement.style.display = 'none';
  //     console.log('②番目だよ');
  //     console.log('カーテン要素をnoneに');
  //   }, 1500);
  //   //bodyにclass'pageOn'を付加
  //   document.body.classList.add('pageOn');
  //   console.log('①番目だよ');
  //   console.log('bodyにpageOn');
  // });
  //END: カーテンをdisplay:none; & Bodyに"pageOn"を付加(関数に入れない)

  /*コールバック関数 */

  //カーテンフェードイン & フェードアウト
  function curtainFadeInOut() {
    setTimeout(() => {
      setTimeout(() => {
        bodyElement.style.display = 'none';
        console.log('④bodyがstyle:noneとなる');
      }, 3000); //デフォルト数値は1000
      bodyElement.classList.add('fadeout');
      console.log('③bodyにfadeoutが付加される');
    });
  }

  // function spinnerFadeOut() {
  //   //ここにカーテンフェードインとスピナーフェードアウトを書く
  // }

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
      const loader = document.querySelector('.p-loader');
      //START: ローディング
      setTimeout(() => {
        setTimeout(() => {
          curtainFadeInOut;
          console.log('if 2番めの処理です');
        }, 2000);
        loader.classList.add('loaded');
        console.log('if 1番めの処理です');
      }, 0);
    } else {
      // 2回目アクセスの処理
      loader.remove();
      console.log('else 1番目の処理です');
      curtainFadeInOut;
      console.log('else 2番めの処理です');
    }
  });
};

export default mycurtain;
