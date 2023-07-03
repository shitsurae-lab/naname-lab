//GSAP
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

function myScrollTrigger() {
  console.log('myScrollTrigger、読みこめてるよ');
}

export default myScrollTrigger;
