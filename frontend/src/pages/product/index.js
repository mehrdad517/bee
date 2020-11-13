import React, {useEffect, memo, useState} from 'react';
import Container from "@material-ui/core/Container";
import {Helmet} from "react-helmet";
import {useDispatch, useSelector} from "react-redux";
import Loading from "../../components/Loading";
import {addToCart, product} from "../../redux/actions";
import {Link} from "react-router-dom";
import Grid from "@material-ui/core/Grid";
import Button from "@material-ui/core/Button";
import './style.css'
import Breadcrumbs from "@material-ui/core/Breadcrumbs";
import NavigateNextIcon from "@material-ui/icons/NavigateBefore";
import ProductSwiper from "../../components/ProductSwiper";
import ProductNavigationMemo from "../../components/Product/NavigationMemo";
import CurrencyFormat from 'react-currency-format';
import {Paper} from "@material-ui/core";
import HelmetProduct from "../../components/Product/HelmetMemo";
import AddShoppingCartIcon from '@material-ui/icons/AddShoppingCart';
import {AUTH_DIALOG} from "../../redux/types";

export const Product = (props) => {

    const { match } = props;

    const dispatch = useDispatch();
    const AppState = useSelector(state => state);
    const [galleryImg, setGalleryImg] = useState('');
    const [loading, setLoading] = useState(false);

    useEffect(() => {

        dispatch(product(match.params.id));

        setTimeout(() => {
            const element = document.querySelector('body');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 200);

    }, []);

    useEffect(() => {
        if (AppState.card.ready === 'request') {
            setLoading(true);
        }
        if (AppState.card.ready === 'success' || AppState.card.ready === 'failure') {
            setLoading(false);
        }
    }, [AppState.card.ready]);


    const renderProduct = () => {

        try {

            const ProductState = AppState.product[match.params.id];

            if (!ProductState || ProductState.ready === 'invalid' || ProductState.ready === 'request')
                return <Loading />;

            if (!ProductState || ProductState.ready === 'failure')
                return <p>{ProductState.error}</p>;


            const { product, categories, similar } = ProductState.data;
            const brand = JSON.parse(product.brand);
            const galleries = JSON.parse(product.files);

            return (
                <React.Fragment>
                    <HelmetProduct product={product}/>
                    <Grid xs={12} item={true}>
                        <ProductNavigationMemo list={categories} current={product.title} />
                    </Grid>
                    <div className="Product">
                        <Grid container={true} spacing={3}>
                            <Grid item lg={4} md={4} sm={12} xs={12}>
                                {galleries && galleries.length > 0 &&
                                <div className="gallery">
                                    <div className="gallery-top">
                                        <img
                                            src={galleryImg ? galleryImg : galleries[0].prefix + '/300/' + galleries[0].file}/>
                                    </div>
                                    <div className="gallery-little-pic-cover">
                                        {galleries.map((item, index) => {
                                            return (
                                                <div key={index} onClick={() => setGalleryImg(item.prefix + '/300/' + item.file)}
                                                     className="gallery-little-pic">
                                                    <img src={item.prefix + '/100/' + item.file}/>
                                                </div>
                                            )
                                        })
                                        }
                                    </div>
                                    <div className="social-gallery"></div>
                                </div>
                                }
                            </Grid>
                            <Grid item lg={5} md={4} sm={12} xs={12}>
                                <div className="product-main">
                                    <div className="product-main-title">
                                        <h1>{product.title}</h1>
                                        <h2>{product.heading}</h2>
                                    </div>
                                    <div>
                                        <ul className="product-directory">
                                            <li>
                                                <span className="product-directory-title">برند</span>
                                                <span>:</span>
                                                <Link to={'/brand/' + brand.id + '/' + brand.slug}><span className="product-directory-text">{brand.title}</span></Link>
                                            </li>
                                            <li>
                                                <span className="product-directory-title">دسته بندی</span>
                                                <span>:</span>
                                                {categories.map((category, index) => {
                                                    if (category.is_main) {
                                                        return(
                                                            <div key={index}>
                                                                <Link to={'/category/' + category.id + '/' + category.slug}><span className="product-directory-text">{category.title}</span></Link>
                                                                {(categories.length - 1) !== index && <span>&nbsp;,</span>}
                                                            </div>
                                                        );
                                                    }
                                                })}
                                            </li>
                                        </ul>
                                    </div>
                                    <p className="product-main-about">{product.short_content}</p>
                                    <div className="price-box-inner">
                                        <Button
                                            onClick={() => AppState.auth.login ?  dispatch(addToCart({count: 1, id: product.id})) : dispatch({type : AUTH_DIALOG, payload: true}) }
                                            disabled={loading}
                                            variant="contained"
                                            fullWidth={true}
                                            color="primary"
                                            startIcon={!loading ? <AddShoppingCartIcon/> : ''}
                                        >
                                            {!loading ?  "افزودن به سبد خرید" : <span className={'loading-on-btn'} />}
                                        </Button>
                                    </div>
                                </div>
                            </Grid>
                            <Grid item lg={3} md={4} sm={12} xs={12}>
                                <div className="main-price-box">
                                    <Grid container={true}>
                                        <Grid xs={12} item={true}>
                                            <div className="price-box-money">
                                                {product.discount > 0 && <div className="inventory">{"قیمت"}:&nbsp;<span className="inventory-number"><del><CurrencyFormat value={product.price} displayType="text" thousandSeparator/></del></span></div>}
                                                <div className="price-money">
                                                    <CurrencyFormat value={product.off_price} displayType="text" thousandSeparator/>
                                                    <span>تومان</span>
                                                </div>
                                            </div>
                                        </Grid>
                                    </Grid>
                                    {product.discount > 0 && <span className="price-off-badge">{(product.discount) + '%'}</span>}
                                </div>
                            </Grid>
                        </Grid>
                    </div>
                    {product.content && <Paper className={'product-content'}>
                        <p className="product-main-about" dangerouslySetInnerHTML={{__html: product.content}}/>
                    </Paper>}
                    {similar.length > 0 && <ProductSwiper lists={[{title : 'محصولات مشابه', products : similar}]} />}
                </React.Fragment>
            )

        } catch (e) {
            return <Loading />;
        }



    };

    return (
        <Container>
            {renderProduct()}
        </Container>
    );
};

export default Product;
