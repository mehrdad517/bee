import React, {useEffect, memo, useState} from 'react';
import Container from "@material-ui/core/Container";
import {useHistory, useLocation} from "react-router";
import Grid from "@material-ui/core/Grid";
import { Helmet } from 'react-helmet';
import { Link } from 'react-router-dom';
import NavigateNextIcon from '@material-ui/icons/NavigateBefore';
import Breadcrumbs from '@material-ui/core/Breadcrumbs';
import {Paper} from "@material-ui/core";
import {useDispatch, useSelector} from "react-redux";
import {blog} from "../../redux/actions";
import Loading from "../../components/Loading";
import PostBox from "../../components/PostBox";
import Paginator from "../../components/Paginator";
import {ENV} from "../../config/env";
import BlogMenu from "../../components/Blog/Menu";

export const Blog = (props) => {

    const {  match } = props;
    const history = useHistory();
    const dispatch = useDispatch();

    const AppState = useSelector(state => state);

    const [page, setPage] = useState(1);

    useEffect(() => {

    }, []);


    useEffect(() => {

        dispatch(blog({ page: page }));

        setTimeout(() => {
            const element = document.querySelector('body');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 500);

    }, [page]);


    const renderBlog = () => {

        const request = AppState.blog;

        if (!request || request.ready === 'invalid' || request.ready === 'request')
            return <Loading />;

        if (request.ready === 'failure') {
            if (request.err === 'Request failed with status code 404') {
                return  history.push('/404.html');
            }
        }


        return (
            <>
                <Helmet>
                    <title>
                        {match.params.tag === undefined && match.params.id === undefined ? AppState.setting.data.blog_title : (AppState.blog.data.result.category.meta_title ? AppState.blog.data.result.category.meta_title : AppState.blog.data.result.category.label)}
                    </title>
                    <meta
                        name="description"
                        content={match.params.tag === undefined && match.params.id === undefined ? AppState.setting.data.blog_description : (AppState.blog.data.result.category.meta_description)}
                    />
                    {match.params.id && (
                        <meta
                            name="canonical"
                            content={`${ENV["MAIN_DOMAIN"]}/blog/category/${match.params.id}/${match.params.slug}`}
                        />
                    )}
                    {match.params.tag && (
                        <meta
                            name="canonical"
                            content={`${ENV["MAIN_DOMAIN"]}/blog/tag/${match.params.tag}`}
                        />
                    )}
                    {match.params.tag === undefined &&
                    match.params.id === undefined && (
                        <meta name="canonical" content={`${ENV["MAIN_DOMAIN"]}/blog`} />
                    )}
                </Helmet>
                <Paper style={{ padding: '10px', margin: '10px 0' }}>
                    <Breadcrumbs
                        separator={<NavigateNextIcon fontSize="small" />}
                        aria-label="breadcrumb"
                    >
                        <Link to="/blog">وبلاگ</Link>
                        {match.params.tag && (
                            <Link
                                to={`/blog/tag/${AppState.blog.data.result.category.label}`}
                            >
                                {AppState.blog.data.result.category.label}
                            </Link>
                        )}
                        {AppState.blog.data.result.category.parents ? AppState.blog.data.result.category.parents.map((nav, index) => {
                                return (
                                    <Link key={index} to={`/blog/${nav.slug}`}>
                                        {nav.label}
                                    </Link>
                                );
                            }
                        ) : <Link to={`/blog/${AppState.blog.data.result.category.id}/${AppState.blog.data.result.category.slug}`}>
                            {AppState.blog.data.result.category.title}
                        </Link>}
                    </Breadcrumbs>
                </Paper>
                <Grid spacing={2} container>
                    <Grid item={true} xs={12} sm={3}>
                        <BlogMenu />
                    </Grid>
                    <Grid item={true} xs={12} sm={9}>
                        <Grid spacing={1} container>
                        {AppState.blog.data.result.contents.data.map((item, index) => {
                            return (
                                <Grid item key={index} xs={12} sm={12} md={6}>
                                    <PostBox key={index} item={item} />
                                </Grid>
                            );
                        })}
                        </Grid>
                    </Grid>
                </Grid>
                <Grid item xs={12}>
                    <Paginator
                        activePage={page}
                        itemsCountPerPage={AppState.blog.data.result.contents.per_page}
                        totalItemsCount={AppState.blog.data.result.contents.total}
                        onChange={(page) => setPage(page)}
                    />
                </Grid>
            </>
        )

    };

    return (
            <Container>
                    {renderBlog()}
            </Container>
    );
};


export default memo(Blog)
