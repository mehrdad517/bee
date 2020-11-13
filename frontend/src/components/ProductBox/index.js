import React, {memo, useEffect, useState} from "react";
import './style.css'
import {ENV} from "../../config/env";
import surface from './../../assets/img/surface.png'
import CurrencyFormat from "react-currency-format";
import {Tooltip} from "@material-ui/core";
import {Link} from "react-router-dom";
import {AUTH_DIALOG} from "../../redux/types";
import {addToCart} from "../../redux/actions";
import {useDispatch, useSelector} from "react-redux";

const ProductBox = memo((props) => {

    const { item } = props;
    const dispatch = useDispatch();
    const AppState = useSelector(state => state);
    let [loading, setLoading] = useState(false);

    useEffect(() => {
        if (AppState.card.ready === 'request') {
            setLoading(true);
        }
        if (AppState.card.ready === 'success' || AppState.card.ready === 'failure') {
            setLoading(false);
        }
    }, [AppState.card.ready]);

    return(
        <div  className="categoryMainBox" >
            <Tooltip title={`مشاهده محصول`} placement="top-end">
                <Link to={`/product/${item.id}/${item.slug}`}>
                    <div className="categoryBoxImage">
                        <img src={ENV["STORAGE"] + '/product/' + item.id + '/200/' + item.img} />
                    </div>
                </Link>
            </Tooltip>
            <Tooltip title={`افزودن به سبد خرید`} placement="top-start">
                <div className='categoryBox' onClick={() => AppState.auth.login ?  dispatch(addToCart({count: 1, id: item.id})) : dispatch({type : AUTH_DIALOG, payload: true}) }>
                    <span className='categoryBoxTitle'>{item.title && item.title.substr(0,28)}</span>
                    {item.price !== item.off_price && <span className="categoryOff"><CurrencyFormat value={item.price} displayType="text" thousandSeparator/></span>}
                    <div  className="categoryPriceBox">
                        <div className="categoryPrice">
                            <span><CurrencyFormat value={item.off_price} displayType="text" thousandSeparator/>&nbsp;تومان</span>
                        </div>
                        <div className="categoryPriceBtn">
                            {!loading ? <img src={surface}/> : <span className={'loading-on-btn'} />}
                        </div>
                    </div>
                </div>
            </Tooltip>
        </div>
    );
});


export default ProductBox;