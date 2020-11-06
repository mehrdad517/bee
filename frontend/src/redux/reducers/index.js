import { combineReducers } from 'redux';
import authReducers from './auth'
import settingReducers from './setting'
import blogReducers from './blog'
import productSwiperReducers from "./productSwiper";
import brandReducers from './brand'
import sliderReducers from './slider'


const rootReducer = combineReducers({
    authReducers,
    settingReducers,
    blogReducers,
    productSwiperReducers,
    brandReducers,
    sliderReducers
});

export default rootReducer;