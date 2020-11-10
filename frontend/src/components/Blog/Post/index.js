import React, {memo} from "react";
import './style.css'
import Grid from '@material-ui/core/Grid';
// @ts-ignore
import {Paper} from "@material-ui/core";
import { Link } from 'react-router-dom';

import Chip from '@material-ui/core/Chip';
// @ts-ignore
import moment from 'moment-jalaali';
import Breadcrumbs from '@material-ui/core/Breadcrumbs';
import TodayOutlinedIcon from '@material-ui/icons/TodayOutlined';
import NavigateNextIcon from '@material-ui/icons/NavigateBefore';
import AccountCircleIcon from '@material-ui/icons/AccountCircle';
import {ENV} from "../../../config/env";
import Gallery from "../../Gallery";


// @ts-ignore
const PostPage = (props) => {

    const { item } = props;

    console.log(item)

    return(
        <div className='post'>
            <Paper component={"div"} className="post-navigation">
                <Breadcrumbs separator={<NavigateNextIcon fontSize="small" />}>
                    <Link color="inherit" to="/">
                        بی نتورک
                    </Link>
                    <Link color="inherit" to="/blog">
                        وبلاگ
                    </Link>
                    <b>{item.title}</b>
                </Breadcrumbs>
            </Paper>
            <Paper component={"div"} className="post-title">
                <h1 className="post-title-text">{item.title}</h1>
                <h2 className="post-title-heading">
                    {item.heading}
                </h2>
                <ul className="post-title-list">
                    {item.created_by && (
                        <li>
                            <AccountCircleIcon />&nbsp;
                            {item.created_by.name}
                        </li>
                    )}
                    {item.created_at && (
                        <li>
                            <TodayOutlinedIcon />
                            &nbsp;
                            <time>
                                {moment(
                                    item.created_at,
                                    'YYYY/MM/DD HH:mm:ss'
                                )
                                    .locale('fa')
                                    .format('jYYYY/jMM/jDD HH:mm:ss')}
                            </time>
                        </li>
                    )}
                </ul>
            </Paper>
            <Grid spacing={2} container={true}>
                <Grid item lg={9} md={9} sm={12} xs={12}>
                    <Paper className="post-description">
                        {item.img &&  (
                            <img className="img-box" src={ENV.API.STORAGE + `/content/${item.id}/` + '/' + item.img}/>
                        )}
                        <div className="paragraph-box">
                            <p dangerouslySetInnerHTML={{ __html: item.content}} />
                        </div>
                    </Paper>
                </Grid>
                <Grid item lg={3} md={3} sm={12} xs={12}>
                    <aside>
                        {item.categories && item.categories.length > 0 && item.categories.map((category, index) => {
                            return (
                                <div key={index}>
                                    {category.contents && (
                                        <div key={index} className="suggested-articles">
                                            <h5 className="suggested-articles-title">
                                                {category.title}
                                            </h5>
                                            <ul className="suggested-articles-list">
                                                {category.contents.map((content, index) => {
                                                    return (
                                                        <li key={index}>
                                                            {content.img &&  (
                                                                <img  src={ENV["STORAGE"] + `/content/${content.id}/` + '/50/' + content.img}/>
                                                            )}
                                                            <div>
                                                                <p>
                                                                    <Link to={`/post/${content.id}/${content.slug}`}>
                                                                        {content.title}
                                                                    </Link>
                                                                </p>
                                                                <time>
                                                                    {moment(
                                                                        content.created_at,
                                                                        'YYYY/MM/DD HH:mm:ss'
                                                                    )
                                                                        .locale('fa')
                                                                        .format('jYYYY/jMM/jDD HH:mm:ss')}
                                                                </time>
                                                            </div>
                                                        </li>
                                                    );
                                                })}
                                            </ul>
                                        </div>
                                    )}
                                </div>
                            );
                        })}
                        {item.files && item.files.length > 0 && <div className="post-gallery-articles">
                            <h5 className="post-gallery-articles-title">
                                گالری تصاویر
                            </h5>
                            <div className="post-gallery">
                                <Gallery list={item.files} />
                            </div>
                        </div>}
                        {item.tags && item.tags.length > 0 && (
                            <div className="post-tags">
                                <ul>
                                    {item.tags.map((tag, index) => {
                                        return (
                                            <li key={index}>
                                                <Chip
                                                    component={Link}
                                                    to={`/blog/tag/${tag.id}/${tag.name.replace(/\s+/g, '-')}`}
                                                    variant="outlined"
                                                    color="primary"
                                                    clickable
                                                    size="small"
                                                    label={tag.name}
                                                />
                                            </li>
                                        );
                                    })}
                                </ul>
                            </div>
                        )}
                    </aside>
                </Grid>
            </Grid>
        </div>
    );
}

export default memo(PostPage);