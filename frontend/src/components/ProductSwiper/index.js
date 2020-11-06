import React, { memo } from 'react';
import Grid from '@material-ui/core/Grid';
import Swiper from 'react-id-swiper';
import 'swiper/swiper-bundle.css'
import Line from "../Line";
import ProductBox from "../ProductBox";


const params = {
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
    },
    renderPrevButton: () => <div className="swiper-button-prev"></div>,
    renderNextButton: () => <div className="swiper-button-next"></div>,
    spaceBetween: 0,
    breakpoints: {
        1024: {
            slidesPerView: 5,
            spaceBetween: 30
        },
        768: {
            slidesPerView: 3,
            spaceBetween: 20
        },
        640: {
            slidesPerView: 3,
            spaceBetween: 10
        },
        550: {
            slidesPerView: 2,
            spaceBetween: 10
        },
        400: {
            slidesPerView: 1.7,
            spaceBetween: 20
        },
        375: {
            slidesPerView: 1.7,
            spaceBetween: 20
        },
        360: {
            slidesPerView: 1.5,
            spaceBetween: 20
        },
        320: {
            slidesPerView: 1.3,
            spaceBetween: 20
        }
    },
}


const ProductSwiper =  memo(({ lists }) => (
    <Grid item xs={12}>
        {lists.map((list, index) => {
            return (
                <div style={{ margin: '40px 0'}} key={index}>
                    <Line title={typeof list.title !== "undefined" ? list.title: list.label} link={list.link !== null ? list.link : ''} />
                    <div style={{ marginTop: '40px'}}>
                        <Swiper {...params}>
                            {list.products.map((item, index) => {
                                return (
                                    <div key={index}>
                                        <ProductBox item={item} />
                                    </div>
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