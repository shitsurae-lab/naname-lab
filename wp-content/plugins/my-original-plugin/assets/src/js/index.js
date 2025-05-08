import '../css/style.scss';

import gnav from './gnav';
gnav();

// Beer CSS
// import 'beercss/scoped';
// import 'material-dynamic-colors';

//オープニングアニメーション
import openingAnime from './openingAnime';
openingAnime();

import headerAnimation from './headerAnimation';
headerAnimation();

//-- addSelector
import addSelector from './addSelector';
addSelector();

//Intersection Observer - 1
import { intersection, timeline } from './intersection';
document.addEventListener('DOMContentLoaded', () => {
  intersection();
  timeline();
});

//-- START GSAP & ScrollTrigger

import {
  initScrollAnimations,
  myScroll,
  myTimeline,
  myTimeline2,
} from './myScrollTrigger';

// DOMContentLoaded 後に初期化関数を呼び出す
document.addEventListener('DOMContentLoaded', () => {
  initScrollAnimations();
  myScroll();
  myTimeline();
  myTimeline2();
});

//ローカルナビゲーション
import { localNav, globalNav } from './navigation';
document.addEventListener('DOMContentLoaded', () => {
  localNav();
  globalNav();
});

// const FuncTime = (hoge = 10, fuga = 20, piyo = 30) => {
//   const arr = [hoge, fuga, piyo];
//   if (arr.length) {
//     console.log('arrはtrue');
//   }
//   arr.forEach((elm) => {
//     console.log(elm);
//   });
//   return arr;
// };

// const test = FuncTime; //関数を代入するから文末は();ではなく、;にする
// test(); //関数が代入された変数を実行するので文末は();でOK

import CreateSVG from './createSVG';
CreateSVG();

import CreateWave from './CreateWave';
CreateWave();

//--- START: Swiper ---
//myswiperは関数が指定されているわけではないので()による関数の実行はしない
import {
  swiperMini,
  swiperGallery,
  heroSwiper,
  indexSwiper,
  index_hero_slider,
  infinite_loopSlider,
  postSwiper,
} from './myswiper';

//-- START summary・detailアニメーション
import accordionAnim from './accordion';
accordionAnim();
//--accordionAnimの中にクリックイベントが記載されているので、accordionAnim();(->関数実行)を行う

import particles from './particles';
particles();

//--Font awesome
import '@fortawesome/fontawesome-free/js/fontawesome';
// import '@fortawesome/fontawesome-free/js/solid';
import {
  faFolder,
  faSquareCheck,
} from '@fortawesome/fontawesome-free/js/regular';
