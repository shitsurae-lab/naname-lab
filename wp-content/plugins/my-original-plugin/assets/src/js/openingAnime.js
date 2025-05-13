// モジュール関数として定義
const openingAnime = () => {
  // ① bodyタグを取得
  const bodyElement = document.querySelector('body');

  // ② スピナー表示のラッパー要素
  const opening = document.createElement('div');
  opening.setAttribute('class', 'p-spinner__wrapper');

  // スピナー本体
  const spinner = document.createElement('div');
  spinner.setAttribute('class', 'p-spinner');

  // ローダー（初回のみ表示）
  const loader = document.createElement('div');
  loader.setAttribute('class', 'p-loader');
  const loaderContent = document.createElement('div');
  loaderContent.setAttribute('class', 'p-loader__container');
  loader.prepend(loaderContent);
  const loaderText = document.createElement('p');
  loaderText.setAttribute('class', 'p-loader__text u-uppercase');
  loaderText.innerText = 'A developer who designs.\nA designer who codes.';
  loaderContent.prepend(loaderText);

  // カーテン要素（共通演出）
  const curtainElement = document.createElement('div');
  curtainElement.setAttribute('class', 'p-curtain');

  // 要素の追加順序
  bodyElement.prepend(opening); // スピナー
  opening.prepend(spinner); // スピナーの中身
  opening.after(loader); // スピナーの次にローダー
  loader.after(curtainElement); // ローダーの次にカーテン

  // 初回アクセス判定用関数
  const isFirstAccess = () => {
    if (sessionStorage.getItem('access_flg')) {
      return false; // 2回目以降
    } else {
      sessionStorage.setItem('access_flg', 'true');
      return true; // 初回アクセス
    }
  };

  const isFirst = isFirstAccess();

  // アニメーション用クラス追加関数
  const js_opening = () => {
    opening.classList.add('loaded'); // スピナーの開始アニメーション
  };

  const js_loader = () => {
    loader.classList.add('loaded'); // ローダーテキストアニメーション（初回のみ）
  };

  const js_curtain = () => {
    curtainElement.classList.add('loaded', 'change'); // カーテンの展開
  };

  const js_pageOn = () => {
    document.body.classList.add('pageOn'); // ページの表示ONクラス
    curtainElement.classList.remove('change'); // カーテンを閉じるアニメーション解除
    opening.remove(); // スピナー要素を削除
  };

  // 遅延処理のユーティリティ（Promiseで待機）
  const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

  // アニメーションの実行フロー（非同期で順序管理）
  const runAnimation = async () => {
    js_opening(); // スピナー表示開始

    if (isFirst) {
      await delay(1200); // スピナーが動く時間
      js_loader(); // ローダー（テキスト）を表示
      await delay(2000); // ローダーの演出時間
    }

    js_curtain(); // カーテン表示
    await delay(1000); // カーテンが開くまで待機

    js_pageOn(); // ページ表示完了（bodyにclass追加）
  };

  // 全てのリソース読み込みが完了した後にアニメーション開始
  window.addEventListener('load', () => {
    runAnimation(); // アニメーション実行
  });
};

export default openingAnime;
