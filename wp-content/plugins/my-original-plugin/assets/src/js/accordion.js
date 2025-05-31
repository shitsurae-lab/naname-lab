import { gsap } from 'gsap';

const accordionAnim = () => {
  const [...details] = document.querySelectorAll('.js-details');
  const RUNNING_VALUE = 'running'; // アニメーション実行中のときに付与する予定のカスタムデータ属性の値
  const IS_OPENED_CLASS = 'is-opened'; // アイコン操作用のクラス名

  details.forEach((element) => {
    const summary = element.querySelector('.js-summary');
    const content = element.querySelector('.js-content');

    summary.addEventListener('click', (event) => {
      // デフォルトの挙動を無効化
      event.preventDefault();

      // 連打防止用。アニメーション中だったらクリックイベントを受け付けないでリターンする
      if (element.dataset.animStatus === RUNNING_VALUE) {
        return;
      }

      // detailsのopen属性を判定
      if (element.open) {
        // アコーディオンを閉じるときの処理
        // アイコン操作用クラスを切り替える(クラスを取り除く)
        element.classList.toggle(IS_OPENED_CLASS);

        // アニメーションを実行
        const closingAnim = content.animate(
          closingAnimKeyframes(content),
          animTiming
        );
        // アニメーション実行中用の値を付与
        element.dataset.animStatus = RUNNING_VALUE;

        // アニメーションの完了後に
        closingAnim.onfinish = () => {
          // open属性を取り除く
          element.removeAttribute('open');
          // アニメーション実行中用の値を取り除く
          element.dataset.animStatus = '';
        };
      } else {
        // アコーディオンを開くときの処理
        // open属性を付与
        element.setAttribute('open', 'true');

        // アイコン操作用クラスを切り替える(クラスを付与)
        element.classList.toggle(IS_OPENED_CLASS);

        // アニメーションを実行
        const openingAnim = content.animate(
          openingAnimKeyframes(content),
          animTiming
        );
        // アニメーション実行中用の値を入れる
        element.dataset.animStatus = RUNNING_VALUE;

        // アニメーション完了後にアニメーション実行中用の値を取り除く
        openingAnim.onfinish = () => {
          element.dataset.animStatus = '';
        };
      }
    });
  });

  /**
   * アニメーションの時間とイージング
   */
  const animTiming = {
    duration: 400,
    easing: 'ease-out',
  };

  /**
   * アコーディオンを閉じるときのキーフレーム
   */
  const closingAnimKeyframes = (content) => [
    {
      height: content.offsetHeight + 'px', // height: "auto"だとうまく計算されないため要素の高さを指定する
      opacity: 1,
    },
    {
      height: 0,
      opacity: 0,
    },
  ];

  /**
   * アコーディオンを開くときのキーフレーム
   */
  const openingAnimKeyframes = (content) => [
    {
      height: 0,
      opacity: 0,
    },
    {
      height: content.offsetHeight + 'px',
      opacity: 1,
    },
  ];
};

const accordionSidebar = () => {
  const allDetails = document.querySelectorAll('details.accordion');
  allDetails.forEach((details) => {
    const summary = details.querySelector('summary.c-sidebar__head');
    const content = details.querySelector('.c-sidebar__body.js-content');

    let isAnimating = false; // アニメーション中かどうかのフラグ

    // 初期状態
    gsap.set(content, { height: 0, overflow: 'hidden' });
    details.open = false;

    summary.addEventListener('click', (e) => {
      e.preventDefault();
      if (isAnimating) return; //連打防止
      isAnimating = true;

      const isOpen = details.hasAttribute('open');

      if (isOpen) {
        // 閉じるアニメーション
        gsap.to(content, {
          height: 0,
          duration: 0.4,
          ease: 'power2.inOut',
          onComplete: () => {
            details.removeAttribute('open');
            isAnimating = false; // アニメーション終了後にフラグ解除
          },
        });
      } else {
        //  他の open を閉じる（複数開閉対応）
        allDetails.forEach((other) => {
          if (other !== details && other.hasAttribute('open')) {
            const otherContent = other.querySelector('.js-content');
            gsap.to(otherContent, {
              height: 0,
              duration: 0.4,
              ease: 'power2.inOut',
              onComplete: () => {
                other.removeAttribute('open');
              },
            });
          }
        });

        details.setAttribute('open', 'true');
        // 一度高さを自動で取得してからセット
        const fullHeight = content.scrollHeight;

        gsap.fromTo(
          content,
          { height: 0 },
          {
            height: fullHeight,
            duration: 0.4,
            ease: 'power2.out',
            onComplete: () => {
              isAnimating = false; // アニメーション終了後にフラグ解除
            },
          }
        );
      }
    });
  });
};

const accordion = () => {
  accordionAnim();
  accordionSidebar();
};

export default accordion;
