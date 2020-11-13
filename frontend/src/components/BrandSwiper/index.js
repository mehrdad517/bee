import React, { memo } from 'react';
import 'swiper/swiper-bundle.css'
import './style.css'
import Line from "../Line";

import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/swiper-bundle.min.css';
import SwiperCore, { Navigation, Pagination, Scrollbar, A11y, Autoplay } from 'swiper';
import {Link} from "react-router-dom";
import brandIcon4 from './../../assets/img/fresh.png'
import brandIcon1 from './../../assets/img/PNG/lifestyle.png'
import brandIcon2 from './../../assets/img/PNG/relax.png'
import brandIcon3 from './../../assets/img/PNG/biogripe.png'

SwiperCore.use([Navigation, Pagination, Scrollbar, A11y, Autoplay]);

const params = {
    // slidesPerView: props.slidesPerView,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: false,
    loopFillGroupWithBlank: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
    // autoplay: {
    //     delay: 2500,
    //     disableOnInteraction: false
    // },
    breakpoints: {
        1024: {
            slidesPerView: 7,
            spaceBetween: 20
        },
        768: {
            slidesPerView: 4,
            spaceBetween: 30
        },
        640: {
            slidesPerView: 2,
            spaceBetween: 30
        },
        320: {
            slidesPerView: 1.5,
            spaceBetween: 30
        }
    }
}

const Brand = memo(({lists}) => (
    <>
        <Line title={'برندها'} link={''} />
        <div className='brand-box'>
            <Swiper navigation {...params}>
                {lists.map((brand, index) => {
                    return (
                        <>
                        <SwiperSlide key={index}>
                                <Link to={`/brand/${brand.id}/${brand.slug}`}>
                                    <div className='img-box'>
                                        <img src={brandIcon1}/>
                                    </div>
                                    <b>{brand.title.substr(0,25)}</b>
                                </Link>
                        </SwiperSlide>
                        </>
                    );
                })}
            </Swiper>
        </div>
    </>

));

export default Brand;