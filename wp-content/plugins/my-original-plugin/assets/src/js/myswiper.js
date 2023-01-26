// import Swiper JS
import Swiper, { Pagination, Autoplay, EffectFade } from 'swiper';
// import swiper styles
import 'swiper/css';
// import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import 'swiper/css/effect-fade';

const heroDelay = 7000;
let timer;

const switchAnimation = () => {
  clearTimeout(timer);
  let activeSlide = document.querySelectorAll(
    '.hero .swiper-slide[class*=-active]'
  );
  for (let i = 0; i < activeSlide.length; i++) {
    activeSlide[i].classList.remove('anm-finished');
    activeSlide[i].classList.add('anm-started');
  }
  timer = setTimeout(() => {
    for (let i = 0; i < activeSlide.length; i++) {
      activeSlide[i].classList.remove('anm-finished');
      activeSlide[i].classList.add('anm-started');
    }
  }, heroDelay - 1000);
};

const finishAnimation = () => {
  let activeSlide = document.querySelectorAll(
    '.hero .swiper-slide.anm-started'
  );
  for (let i = 0; i < activeSlide.length; i++) {
    activeSlide[i].classList.remove('anm-started');
    activeSlide[i].classList.add('anm-finished');
  }
};

const swiperMini = new Swiper('.p-swiperMini', {
  spaceBetween: 0,
  slidesPerView: 3,
  effect: 'slide',
});

const swiperGallery = new Swiper('.p-swiperGallery', {
  thumbs: {
    swiper: swiperMini,
  },
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  // speed: 2000,
  effect: 'slide',
});

const heroSwiper = new Swiper('.swiper.hero-swiper', {
  modules: [Pagination, Autoplay, EffectFade],
  speed: 2000,
  loop: true,
  loopAdditionalSlides: 1,
  effect: 'fade',
  //fadeEffectはfadeするときに文字がかぶらないように設定した
  fadeEffect: {
    crossFade: true,
  },
  // slidesPerGroup: 1, //同時にスライドさせるスライドの数
  //slidesPerView: auto,
  //loopedSlides: 3,//slidesPerViewと連動
  slideToClickedSlide: false,
  pagination: {
    el: '.hero .swiper-pagination',
    //-- 通常のページネーションの場合
    type: 'bullets',
    // bulletElement: 'span', //type: bulletsの場合使用できる。bulletElementデフォルトもspan
    clickable: true,
    // -- スライド番号/総数にする場合
    // type: 'fraction'
  },
  // scrollbar: {
  //   el: '.swiper-scrollbar',
  // },
  autoplay: {
    delay: heroDelay,
    disableOnInteraction: false, //ユーザーの操作があっても自動再生を継続
    waitForTransition: false, //アニメーション間も自動再生を止めない
  },
  followFinger: false,
  on: {
    slideChange: () => {
      finishAnimation();
    },
    slideChangeTransitionStart: () => {
      switchAnimation();
    },
  },
});

const indexSwiper = new Swiper('.p-index__swiper', {
  modules: [Autoplay, EffectFade],
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  loop: true,
  effect: 'fade',
});

const index_hero_slider = new Swiper('.p-index_hero_slider', {
  modules: [Autoplay, EffectFade],
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  loop: true,
  effect: 'fade',
});

const infinite_loopSlider = new Swiper('.infinite__slider', {
  modules: [Autoplay, EffectFade],
  loop: true,
  loopedSlides: 2, //スライドの複製枚数
  slidesPerView: 'auto', //スライド表示枚数 ※loop,loopedslidesを併用する場合はこのオプション必須
  speed: 16000,
  autoplay: {
    delay: 0,
    disableOnInteraction: false,
  },
});

//カスタム投稿タイプSwiper
const postSwiper = new Swiper('.infinite-swiper', {
  modules: [Autoplay],
  loop: true,
  loopedSlides: 3, //スライドの複製枚数
  slidesPerView: 'auto', //スライド表示枚数 ※loop,loopedslidesを併用する場合はこのオプション必須
  speed: 200, //200としたが速度が設定できない可能性あり。
  autoplay: {
    delay: 0,
    disableOnInteraction: false,
  },
});

export {
  swiperMini,
  swiperGallery,
  heroSwiper,
  indexSwiper,
  index_hero_slider,
  infinite_loopSlider,
  postSwiper,
};
