import React, { memo } from 'react';
import Grid from '@material-ui/core/Grid';
import {
    BrowserView,
    MobileView,
    isBrowser,
    isMobile,
    isMobileOnly, isTablet
} from "react-device-detect";
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/swiper-bundle.min.css';
import './style.css'
import SwiperCore, { Navigation, Pagination, Scrollbar, A11y, Autoplay, EffectFade } from 'swiper';

SwiperCore.use([Navigation, Pagination, Scrollbar, A11y, Autoplay, EffectFade]);

const Slider = memo(({ list }) => (


    <div className='slider'>
        <Swiper
            effect="fade"
            spaceBetween={50}
            slidesPerView={1}
            navigation
            pagination={{ clickable: true }}
            centeredSlides={true}
            autoplay={{
                delay: 2500,
                disableOnInteraction: true,
            }}
            scrollbar={{ draggable: true }}
        >
            {list.map((item, index) => {
                if (isMobileOnly) {
                    return (
                        <SwiperSlide  key={index}>
                            {item.link ? <a href={item.link}><img alt={item.caption} src={item.prefix + '/300/' + item.file}/></a> : <img alt={item.caption} src={item.prefix + '/300/' + item.file}/>}
                        </SwiperSlide>
                    );
                }  else {
                    return (
                        <SwiperSlide key={index}>
                            {item.link ? <a href={item.link}><img alt={item.caption} src={item.prefix + '/' + item.file}/></a> : <img alt={item.caption} src={item.prefix + '/' + item.file}/>}
                        </SwiperSlide>
                    );
                }
            })}
        </Swiper>
    </div>

));

export default Slider;