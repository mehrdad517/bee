import React, { memo } from 'react';
import 'swiper/swiper-bundle.css'
import './style.css'
import Line from "../Line";
import brandIcon from './../../assets/img/fresh.png'

const Brand = memo(({lists}) => (
    <>
        <Line title={'برندها'} link={''} />
        {/*<div className='brand-box'>*/}
        {/*    <Swiper {...params}>*/}
        {/*        {lists.map((brand, index) => {*/}
        {/*            return (*/}
        {/*                <div key={index}>*/}
        {/*                    <div className='mini_box'>*/}
        {/*                        <div>*/}
        {/*                            <div className='mini_box_title'>{brand.title.substr(0,25)}</div>*/}
        {/*                        </div>*/}
        {/*                        <div className='mini_box_image'>*/}
        {/*                            <img src={brandIcon}/>*/}
        {/*                        </div>*/}
        {/*                    </div>*/}
        {/*                </div>*/}
        {/*            );*/}
        {/*        })}*/}
        {/*    </Swiper>*/}
        {/*</div>*/}
    </>

));

export default Brand;