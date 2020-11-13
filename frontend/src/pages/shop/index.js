import React, {memo, useEffect, useState} from "react";
import {useDispatch, useSelector} from "react-redux";
import {shop} from "../../redux/actions";
import Loading from "../../components/Loading";
import Container from "@material-ui/core/Container";
import Grid from "@material-ui/core/Grid";
import ProductBox from "../../components/ProductBox";
import {useHistory, useLocation} from "react-router";
import Paginator from "../../components/Paginator";
import filterIcon from '../../assets/img/filterIcon.png'
import Divider from "@material-ui/core/Divider";
import './style.css'
import ShopSortMemo from "../../components/Shop/SortMemo";
import ShopCategoryMemo from "../../components/Shop/CategoryMemo";
import ShopBrandMemo from "../../components/Shop/BrandMemo";
import ShopStockMemo from "../../components/Shop/StockMemo";
import ShopNavigationMemo from "../../components/Shop/NavigationMemo";
import HelmetShop from "../../components/Shop/HelmetMemo";

const Shop = (props) => {

    const { match } = props;

    const location = useLocation();
    const history = useHistory();
    const dispatch = useDispatch();
    const AppState = useSelector(state => state);
    const queryString = require('query-string');
    const query = queryString.parse(location.search);

    let query_brands = [];
    query_brands = (query.brands ? query.brands.split(',') : []).map((item) => {
        return parseInt(item);
    });

    // state container
    const [params, setParams] = useState({
        page: query.page ? parseInt(query.page) : 1,
        sort: query.sort ? parseInt(query.sort) : 0,
        stock: query.stock ? parseInt(query.stock) : 1,
        brands: query_brands,
    });


    console.log(params)

    useEffect(() => {


        if (location.pathname.match('category')) {
            dispatch(shop('category', match.params.id, params));
        } else if (location.pathname.match('brand')) {
            dispatch(shop('brand', match.params.id, params));
        } else if (location.pathname.match('shop')){
            dispatch(shop('shop', '', params));
        }

        setTimeout(() => {
            const element = document.querySelector('body');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 500);

    }, [params.page, params.stock, params.sort,params.brands.join(','),match.params.id]);

    useEffect(() => {

        let n_params = params;

        const keys = Object.keys(query);

        for (let i = 0; i < keys.length; i++) {
            switch (keys[i]) {
                case 'page':
                    if (n_params.page !== parseInt(query.page)) {
                        n_params.page = parseInt(query.page);
                    }
                    break;
                case 'stock':
                    if (n_params.stock !== parseInt(query.stock)) {
                        n_params.stock = parseInt(query.stock);
                    }
                    break;
                case 'sort':
                    if (n_params.sort !== parseInt(query.sort)) {
                        n_params.sort = parseInt(query.sort);
                    }
                    break;
                case 'brands':
                    if (n_params.brands.join(',') !== query.brands) {
                        n_params.brands = query.brands.split(',')
                    }
                    break;
                default:
                    break;
            }
        }

        setParams({...n_params});

    }, [query.page, query.sort, query.stock, query.brands]);



    const _makeQueryStringParams = (type, value) => {

        let n_params = params;
        n_params.page = 1;

        switch (type) {
            case 'page':
                n_params.page = value;
                break;
            case 'stock':
                n_params.stock = value;
                break;
            case 'sort':
                n_params.sort = value;
                break;
            case 'brands':
                n_params.brands = value;
                break;

        }

        // create url
        let url = '?';
        url += `page=${n_params.page}`;
        url += `&stock=${n_params.stock}`;
        url += `&sort=${n_params.sort}`;


        if (params.brands.length > 0) {
            url += '&brands=' + params.brands.join(',')
        }


        if (location.pathname.match('category')) {
            if (match.params.slug) {
                history.push(`/category/${match.params.id}/${match.params.slug}/${url}`);
            } else {
                history.push(`/category/${match.params.id}/${url}`);
            }
        } else if (location.pathname.match('brand')) {
            if (match.params.slug) {
                history.push(`/brand/${match.params.id}/${match.params.slug}/${url}`);
            } else {
                history.push(`/brand/${match.params.id}/${url}`);
            }

        } else if (location.pathname.match('shop')){
            history.push(`/shop/${url}`);
        }

    }

    const handleOnChange = async (event) =>  {
        _makeQueryStringParams(event.target.name, parseInt(event.target.value))
    };


    const handleOnChangeBrands = async (event) => {

        let n_brands = params.brands;

        if (event.target.checked === true) {
            n_brands.push(parseInt(event.target.value));
        } else {
            let index = n_brands.indexOf(parseInt(event.target.value));
            n_brands.splice(index, 1)
        }

        _makeQueryStringParams('brands', n_brands)

    };

    /**
     * return shop
     * @returns {*}
     */
    const renderShop = () => {

        const ShopState = AppState.shop;

        if (!ShopState || ShopState.ready === 'invalid' || ShopState.ready === 'request')
            return <Loading />;

        if (ShopState.ready === 'failure') {
            if (ShopState.err === 'Request failed with status code 404') {
                return  history.push('/404.html');
            }
        }

        return (
            <React.Fragment>
                <HelmetShop/>
                <Grid container={true} spacing={3}>
                    <Grid xs={12} item={true}>
                        {ShopState.data && ShopState.data.cached && ShopState.data.cached.navigation && <ShopNavigationMemo list={ShopState.data.cached.navigation} />}
                    </Grid>
                </Grid>
                <Grid container={true} spacing={2}>
                    <Grid item={true} sm={3}>
                        <div className={'shop-aside'}>
                            <div className={'shop-aside-heading'}>
                                <img src={filterIcon}/>
                                <h3>فیلتر محصولات</h3>
                            </div>
                            <Divider />
                            <ShopStockMemo selected={params.stock}  onChange={(event) => handleOnChange(event)} />
                            <Divider />
                            {ShopState.data && ShopState.data.cached && ShopState.data.cached.brands && ShopState.data.cached.brands && ShopState.data.cached.brands.length > 0 && <ShopBrandMemo onChange={(event) => handleOnChangeBrands(event)} values={params.brands} list={ShopState.data.cached.brands}  />}
                            {ShopState.data && ShopState.data.cached && ShopState.data.cached.tree && ShopState.data.cached.tree.length > 0 && <ShopCategoryMemo list={ShopState.data.cached.tree} />}
                        </div>
                    </Grid>
                    <Grid item={true} sm={9}>
                        <ShopSortMemo onClick={(type, value) => _makeQueryStringParams(type, value)} list={ShopState.data && ShopState.data.cached && ShopState.data.cached.sort} selected={params.sort}/>
                        <Grid container={true} spacing={3}>
                            {ShopState.data.products && ShopState.data.products.data.length > 0 && ShopState.data.products.data.map((item, index) => {
                                return(
                                    <Grid item lg={3} md={4} sm={6} xs={12}>
                                        <ProductBox item={item}/>
                                    </Grid>
                                );
                            })}
                        </Grid>
                    </Grid>
                </Grid>
                {ShopState.data.products && ShopState.data.products.data.length > 0 && ShopState.data.products.total > 1 && <Grid item xs={12}>
                    <Paginator
                        activePage={parseInt(params.page)}
                        itemsCountPerPage={ShopState.data.products.per_page}
                        totalItemsCount={ShopState.data.products.total}
                        onChange={(page) => _makeQueryStringParams('page', page)}
                    />
                </Grid>}

            </React.Fragment>
        );
    };

    return(
        <Container>
            {renderShop()}
        </Container>
    );
};


export default memo(Shop);