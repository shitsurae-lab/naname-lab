import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);
function custom_gsap() {
  console.log('custom_gsap読み込めてます');
  // gsap.set('.js-target-stagger', { autoAlpha: 0 });
}
export default custom_gsap;
