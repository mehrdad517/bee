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
const Home = () => {

    const dispatch = useDispatch();
    const AppState = useSelector(state => state);

    useEffect(() => {


        if (AppState.brandReducers.ready !== 'success') {
            dispatch(brand());
        }

        if (AppState.productSwiperReducers.ready !== 'success') {
            dispatch(productSwiper());
        }

        if (AppState.blogReducers.ready !== 'success') {
            dispatch(blog());
        }

        if (AppState.sliderReducers.ready !== 'success') {
            dispatch(slider());
        }

    }, []);

    const renderSlider = () => {

        const request = AppState.sliderReducers;
        if (!request || request.readyStatus === 'invalid' || request.readyStatus === 'request' || request.readyStatus === 'failure') {
            return null
        }

        return <Slider list={AppState.sliderReducers.data}/>
    }

    const renderBrand = () => {

        const request = AppState.brandReducers;
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

        const request = AppState.blogReducers;
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