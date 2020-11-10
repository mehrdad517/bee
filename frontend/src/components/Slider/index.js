import React, { memo } from 'react';
import Grid from '@material-ui/core/Grid';
import {
    BrowserView,
    MobileView,
    isBrowser,
    isMobile,
    isMobileOnly, isTablet
} from "react-device-detect";
import Swiper from 'react-id-swiper';
import './style.css'

const params = {
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
    },
    effect: 'fade',
    spaceBetween: 30,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false
    },
}

const Slider = memo(({ list }) => (


    <div className='slider'>
        <Swiper {...params}>
            {list.map((item, index) => {
                if (isMobileOnly) {
                    return (
                        <div  key={index}>
                            {item.link ? <a href={item.link}><img alt={item.caption} src={item.prefix + '/500/' + item.file}/></a> : <img alt={item.caption} src={item.prefix + '/500/' + item.file}/>}
                        </div>
                    );
                }  else {
                    return (
                        <div style={{ height: '500px', borderRadius: '10px' }} key={index}>
                            {item.link ? <a href={item.link}><img alt={item.caption} src={item.prefix + '/' + item.file}/></a> : <img alt={item.caption} src={item.prefix + '/' + item.file}/>}
                        </div>
                    );
                }
            })}
        </Swiper>
    </div>

));

export default Slider;