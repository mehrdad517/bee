import { combineReducers } from 'redux';
import authReducers from './auth'
import settingReducers from './setting'
import blogReducers from './blog'
import productSwiperReducers from "./productSwiper";
import brandReducers from './brand'
import sliderReducers from './slider'
import cardReducers from './card'
import addressReducers from "./address";
import regionReducers from './region'

const rootReducer = combineReducers({
    authReducers,
    settingReducers,
    blogReducers,
    productSwiperReducers,
    brandReducers,
    sliderReducers,
    cardReducers,
    addressReducers,
    regionReducers
});

export default rootReducer;