import React, {memo} from "react";
import './style.css'
import {ENV} from "../../config/env";
import surface from './../../assets/img/surface.png'

const ProductBox = memo((props) => {

    const {item} = props;

    return(
        <div  className="categoryMainBox" >
            <div className="categoryBoxImage">
                <img src={ENV["STORAGE"] + '/product/' + item.id + '/200/' + item.img} />
            </div>
            <div  className='categoryBox'>
                <span className='categoryBoxTitle'>{item.title && item.title.substr(0,28)}</span>
                <div  className="categoryPriceBox">
                    <div className="categoryPrice">
                        <span className="categoryOff">{item.price}</span>
                        <span>{item.off_price}&nbsp;تومان</span>
                    </div>
                    <div className="categoryPriceBtn">
                        <img src={surface}/>
                    </div>
                </div>
            </div>
        </div>
    );
});


export default ProductBox;