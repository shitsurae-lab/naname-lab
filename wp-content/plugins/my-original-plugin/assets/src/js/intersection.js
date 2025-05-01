const intersection = () => {
  //①コールバック関数
  const callback = (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('on');
        // console.log('監視範囲に入りました');
        //第２引数の observer の unobserve() メソッドで監視を停止
        observer.unobserve(entry.target);
      } else {
        entry.target.classList.remove('on');
        //console.log('監視範囲を出ました');
      }
    });
  };
  //②オプション
  const options = {
    root: null, //ルート
    rootMargin: '-10% 0px',
    threshold: 0.1,
  };

  //③引数にコールバック関数とオプションを指定してオブザーバーを生成
  const observer = new IntersectionObserver(callback, options);

  //④監視対象の要素をすべて取得
  const [...monitorElements] = document.querySelectorAll('.js-target');

  //⑤すべての監視対象要素をobserve()メソッドに指定
  monitorElements.forEach((elem) => {
    //observe()に監視対象の要素を指定
    observer.observe(elem);
  });
  //console.log('Intersection Observer APIを読み込んでます');
};
// export default intersection;

const timeline = () => {
  const timelineItems = document.querySelectorAll('.js-timeline-item');

  // Intersection Observerの設定
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          // indexによって遅延させる
          setTimeout(() => {
            entry.target.classList.add('is-show');
          }, index * 400); // 1個ずつ400ms遅らせて表示(デフォルトは200ms)

          observer.unobserve(entry.target); // 一度表示されたら監視を解除
        }
      });
    },
    {
      threshold: 0.1, // 画面に10%でも入ったら反応
    }
  );

  // 全アイテムを監視開始
  timelineItems.forEach((item) => {
    observer.observe(item);
  });
};

export { intersection, timeline };
