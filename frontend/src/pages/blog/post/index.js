import React, { useEffect, memo } from 'react';
import {toast} from "react-toastify";
import Container from "@material-ui/core/Container";
import {useHistory} from "react-router";
import {Helmet} from "react-helmet";
import {useDispatch, useSelector} from "react-redux";
import Loading from "../../../components/Loading";
import {ENV} from "../../../config/env";
import PostPage from "../../../components/Blog/Post";
import {post} from "../../../redux/actions";
import './style.css'

export const Post = (props) => {

    const { match } = props;

    const dispatch = useDispatch();
    const AppState = useSelector(state => state);

    useEffect(() => {

        // get post
        dispatch(post(match.params.id));

        setTimeout(() => {
            const element = document.querySelector('body');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 200);

    }, []);


    const renderPost = () => {

        try {

            const request = AppState.post[match.params.id];

            if (!request || request.ready === 'invalid' || request.ready === 'request')
                return <Loading />

            if (request.ready === 'failure') {
                if (request.err === 'Request failed with status code 404') {
                    return <p>در پنل مدیریت یک پست با اسلاگ این صفحه ایجاد کنید.</p>
                }
            }

            return (
                <React.Fragment>
                    <Helmet>
                        <title>{request.data.meta_title}</title>
                        <meta
                            name="description"
                            content={request.data.meta_description}
                        />
                        <meta
                            name="canonical"
                            content={`${ENV["MAIN_DOMAIN"]}/blog/post/${request.data.id}/${request.data.slug}`}
                        />
                    </Helmet>
                    <PostPage item={request.data} />
                </React.Fragment>
            )
        } catch (e) {
            return <Loading />
        }


    };

    return (
        <Container>
            {renderPost()}
        </Container>
    );
};

export default memo(Post);
