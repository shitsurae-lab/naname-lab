const mycurtain = () => {
  //① bodyタグを取得
  const bodyElement = document.querySelector('body');

  //②カーテン要素の作成
  const curtainElement = document.createElement('div');
  curtainElement.setAttribute('class', 'curtain');

  //③loaderを作成
  const loader = document.createElement('div');
  loader.setAttribute('class', 'p-loader');

  //④spinnerを作成
  const spinner = document.createElement('div');
  spinner.setAttribute('class', 'p-spinner');

  //⑤オープニングアニメーションの作成
  const opening = document.createElement('div');
  opening.setAttribute('class', 'p-opening');

  //①body要素内の先頭にp-loader
  bodyElement.prepend(loader);
  //②p-loader要素内の先頭にp-spinner
  loader.prepend(spinner);
  //③ p-loaderの後ろにp-opening
  loader.after(opening);
  //④ p-loaderの後ろにカーテン要素
  opening.after(curtainElement);

  //START: カーテンをdisplay:none; & Bodyに"pageOn"を付加(関数に入れない)
  function js_opening() {
    const opening_inner_elem = '<p>js_openingが読み込まれたよ</p>';
    console.log(opening_inner_elem);
  }

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
  //-- 下記functionは記述に誤りあり。一旦コメントアウト
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
      // const loader = document.querySelector('.p-loader');
      //START: ローディング
      setTimeout(() => {
        setTimeout(() => {
          setTimeout(() => {
            js_secondary();
            console.log('1回目のアクセスの際の「3番めの処理です」');
          }, 1000);
          opening.classList.add('loaded');
          js_opening();
          console.log('1回目のアクセスの際の「2番めの処理です」');
        }, 2000);
        loader.classList.add('loaded');
        js_primary();
        console.log('1回目のアクセスの際の「1番めの処理です」');
      }, 0);
    } else {
      // 2回目アクセスの処理
      loader.remove();
      setTimeout(() => {
        setTimeout(() => {
          // curtainFadeInOut();
          js_secondary();
          console.log('2回目以降のアクセスの際の「2番めの処理です」');
        }, 2000);
        js_primary();
        console.log('2回目以降のアクセスの際の「1番目の処理です」');
      }, 0);
      // loader.remove();
      // console.log('2回目以降のアクセスの際の「1番目の処理です」');
      // curtainFadeInOut;
      // console.log('2回目以降のアクセスの際の「2番めの処理です」');
    }
  });
};

export default mycurtain;
