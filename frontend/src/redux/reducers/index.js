import { combineReducers } from 'redux';
import auth from './auth'
import setting from './setting'
import blog from './blog'
import productSwiperReducers from "./productSwiper";
import brand from './brand'
import slider from './slider'
import card from './card'
import address from "./address";
import region from './region'
import post from './post'
import menu from './menu'

const rootReducer = combineReducers({
    auth,
    setting,
    blog,
    productSwiperReducers,
    brand,
    slider,
    card,
    address,
    region,
    post,
    menu,
});

export default rootReducer;