import React, { memo } from 'react';
import Grid from '@material-ui/core/Grid';
import 'swiper/swiper-bundle.css'
import Line from "../Line";
import ProductBox from "../ProductBox";

import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/swiper-bundle.min.css';
import SwiperCore, { Navigation, Pagination, Scrollbar, A11y, Autoplay } from 'swiper';

SwiperCore.use([Navigation, Pagination, Scrollbar, A11y, Autoplay]);

const params = {
    // slidesPerView: props.slidesPerView,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: true,
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
            slidesPerView: 5,
            spaceBetween: 30
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

const ProductSwiper = memo(({ lists }) => (
    <Grid item xs={12}>
        {lists.map((list, index) => {
            return (
                <div style={{ margin: '20px 0'}} key={index}>
                    <Line title={typeof list.title !== undefined ? list.title: list.label} link={list.link !== undefined && list.link !== null ? list.link : ''} />
                    <div style={{ marginTop: '40px'}}>
                        <Swiper navigation {...params}>
                            {list.products.map((item, index) => {
                                return (
                                    <SwiperSlide key={index}>
                                        <ProductBox key={index} item={item} />
                                    </SwiperSlide>
                                );
                            })}
                        </Swiper>
                    </div>
                </div>
            );
        })}
    </Grid>
));

export default ProductSwiper;