import React, {memo} from "react";
import Grid from '@material-ui/core/Grid';
import DateRangeIcon from '@material-ui/icons/DateRange';
import ShowChartIcon from '@material-ui/icons/ShowChart';
import KeyboardReturnIcon from '@material-ui/icons/KeyboardReturn';
import {Link} from 'react-router-dom';
import moment from 'moment-jalaali';
import {ENV} from "../../config/env";
import './style.css'
import logoIcon from './../../assets/img/logo-min.png'


const PostBox = memo(({item}) => (
    <div className="news-items">
        <div className="news-item">
            <div className="news-item-head">
                <h4>
                    <Link className="news-item-title"
                          to={`/post/${item.id}/${item.slug}`}
                    >
                        {item.title}
                    </Link>
                </h4>
                {item.img && <img className="news-items-img" src={ENV.API.STORAGE + `/content/${item.id}/` + '/100/' + item.img}/>}
            </div>
            <Grid spacing={3} container>
                <ul>
                    <li>
                        <DateRangeIcon  fontSize="small"/>
                        <span style={{direction: 'ltr'}}>
                {moment(item.created_at, 'YYYY/MM/DD HH:mm:ss').locale('fa').format('jYYYY/jMM/jDD HH:mm:ss')}
            </span>
                    </li>
                    {/*<li>*/}
                    {/*    <ShowChartIcon fontSize="small"/>*/}
                    {/*    <span>{item.visitor}</span>*/}
                    {/*</li>*/}
                </ul>
            </Grid>
            <p className='news-item-content' dangerouslySetInnerHTML={{ __html: item.content}} />
            <div className="Continues">
                <Link
                    to={`/post/${item.id}/${item.slug}`}
                >
                    ادامه مطلب
                </Link>
            </div>
        </div>
    </div>
))

export default PostBox;