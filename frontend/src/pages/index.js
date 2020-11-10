import React, {useEffect} from 'react';
import Line from "../components/Line";
import PostList from "../components/PostList";
import Grid from "@material-ui/core/Grid";
import {useDispatch, useSelector} from "react-redux";
import {blog, brand, productSwiper, slider} from "../redux/actions";
import Container from "@material-ui/core/Container";
import ProductSwiper from "../components/ProductSwiper";
import Brand from "../components/BrandSwiper";
import Slider from "../components/Slider";
import moment from "moment-jalaali";
const Home = () => {

    const dispatch = useDispatch();
    const AppState = useSelector(state => state);

    useEffect(() => {


        if (AppState.brand.ready !== 'success' || moment.unix() > AppState.setting.expiration) {
            dispatch(brand());
        }

        if (AppState.productSwiperReducers.ready !== 'success') {
            dispatch(productSwiper());
        }


        if (AppState.blog.ready !== 'success' || moment.unix() > AppState.setting.expiration) {
            dispatch(blog());
        }

        if (AppState.slider.ready !== 'success' || moment.unix() > AppState.setting.expiration) {
            dispatch(slider());
        }

    }, []);

    const renderSlider = () => {

        const request = AppState.slider;
        if (!request || request.readyStatus === 'invalid' || request.readyStatus === 'request' || request.readyStatus === 'failure') {
            return null
        }

        return <Slider list={AppState.slider.data}/>
    }

    const renderBrand = () => {

        const request = AppState.brand;
        if (!request || request.ready === 'invalid' || request.ready === 'request' || request.ready === 'failure') {
            return null
        }

        return <Brand lists={request.data} />;
    };


    const renderProductSwiper = () => {

        const request = AppState.productSwiperReducers;
        if (!request || request.ready === 'invalid' || request.ready === 'request' || request.ready === 'failure') {
            return null
        }

        return <ProductSwiper lists={request.data} />;
    };

    const renderBlog = () => {

        const request = AppState.blog;
        if (!request || request.ready === 'invalid' || request.ready === 'request' || request.ready === 'failure') {
            return null
        }

        return (
            <>
                <Grid item xs={12}>
                    <Line title="آخرین مطالب وبلاگ" link="/blog" />
                </Grid>
                <PostList list={request.data.result.contents.data} />
            </>
        )
    };

    return(
        <div>
            <Container>
                {renderSlider()}
                {renderBrand()}
                {renderProductSwiper()}
                {renderBlog()}
            </Container>
        </div>
    );

};


export default Home;