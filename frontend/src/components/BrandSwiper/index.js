import React, { memo } from 'react';
import Swiper from 'react-id-swiper';
import 'swiper/swiper-bundle.css'
import './style.css'
import Line from "../Line";
import brandIcon from './../../assets/img/fresh.png'
const params = {
    spaceBetween: 0,
    breakpoints: {
        1024: {
            slidesPerView: 5,
            spaceBetween: 50
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


const Brand = memo(({lists}) => (
    <>
        <Line title={'برندها'} link={''} />
        <div className='brand-box'>
            <Swiper {...params}>
                {lists.map((brand, index) => {
                    return (
                        <div key={index}>
                            <div className='mini_box'>
                                <div>
                                    <div className='mini_box_title'>{brand.title.substr(0,25)}</div>
                                </div>
                                <div className='mini_box_image'>
                                    <img src={brandIcon}/>
                                </div>
                            </div>
                        </div>
                    );
                })}
            </Swiper>
        </div>
    </>

));

export default Brand;