import React, {createRef, memo, useEffect, useState} from "react";
import './captcha.css'
import capchaImg from './../../assets/img/captcha.png'
const Captcha = memo((props) => {


    const { onLoad, regenerate } = props;

    const [captcha, setCaptcha] = useState([]);

    const captcha_img = createRef();

    useEffect(() => {
        captchaGenerator()
    }, []);

    useEffect(() => {
        captchaGenerator()
    }, [regenerate]);

    const captchaGenerator = () => {

        let rnd = [];

        for (let i = 0 ; i <= 5 ; i++) {
            rnd[i] = Math.floor(Math.random() * 10).toString();
        }

        setCaptcha(rnd);
    }

    useEffect(() => {

        onLoad(captcha);

        let convas = document.createElement("CANVAS");
        let ctx = convas.getContext('2d');
        ctx.width = 150;
        ctx.height = 50;
        ctx.font = "40px sans-serif"
        ctx.fillText(captcha.join(''), 150, 90);
        captcha_img.current.src = ctx.canvas.toDataURL();

    }, [captcha]);



  return(
      <div className="captcha-box">
          <img onClick={() => captchaGenerator()} width={20} height={20} src={capchaImg}/>
          <img style={{ width: '170px', height: '70px'}} ref={captcha_img}/>
      </div>
  );
})


export default Captcha;