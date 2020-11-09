import React, {useState, memo} from 'react';
import {useSelector} from "react-redux";
import {Grid} from "@material-ui/core";
import Container from '@material-ui/core/Container';
import {Link} from "react-router-dom";
import  './style.css'

const Footer = () => {

    const [sub, setSub] = useState(500);

    const { data } = useSelector(state => state.setting);

    return (
        <div className="Footer">
            <Container>
                <Grid className="FooterTop" container>
                    <Grid item lg={9} md={9} sm={6} xs={12}>
                        <div className="FooterParagraph">
                            <p style={{ maxHeight: sub, overflow: sub === 500 && 'hidden'}}>{data.introduce}</p>
                            {data.introduce && data.introduce.length >= 500 && <span className="loadMore" onClick={() => setSub(sub === 500 ? 2000 : 500)}>{sub === 500 ? 'بیشتر' : 'بستن'}</span>}
                        </div>
                    </Grid>
                    <Grid item lg={3} md={3} sm={6} xs={12}>
                        <div className="FooterImg">
                            <ul>
                                {data.links && data.links.map((license, index) => {
                                    if (license.type === 'license') {
                                        return (
                                            <li key={index}>
                                                <a href={license.value} target="_blank">
                                                    <img alt={license.title} src={(`${data.backend}/static/img/license/${license.id}.png`)}/>
                                                </a>
                                            </li>
                                        );
                                    }
                                })}
                            </ul>
                        </div>
                    </Grid>
                </Grid>
                <Grid style={{ marginTop: '5px' }} container spacing={5}>
                    <Grid item lg={6} md={6} sm={12} xs={12}>
                        {/*<FooterMenu />*/}
                    </Grid>
                    <Grid item lg={4} md={4} sm={6} xs={12}>
                        <h4>پل های ارتباطی</h4>
                        <ul className="contact">
                            {data.links && data.links.map((contact, index) => {
                                if (contact.type === 'contact') {
                                    return (
                                        <li key={index}>
                                            <b>{contact.title}</b>:&nbsp;<span>{contact.value}</span>
                                        </li>
                                    );
                                }
                            })}
                        </ul>
                    </Grid>
                    <Grid item lg={2} md={2} sm={6} xs={12}>
                        <div className="network">
                            <h4>شبکه های اجتماعی</h4>
                            <ul className="networkImg">
                                {data.links && data.links.map((social, index) => {
                                    if (social.type === 'social') {
                                        return (
                                            <li key={index}>
                                                <a href={social.value} target="_blank">
                                                    <img alt={social.title} src={(`${data.backend}/static/img/social/${social.id}.png`)}
                                                    />
                                                </a>
                                            </li>
                                        );
                                    }
                                })}
                            </ul>
                        </div>
                    </Grid>
                </Grid>
                <Grid container>
                    <Grid item xs={12}>
                        <div className="FooterDown">
                            <p>{data.copy_right}</p>
                            <p><b>طراحی و اجرا:</b>&nbsp;<span className={'develop'}>مجموعه نرم افزاری شرکت کیهان کالا پارس</span></p>
                        </div>
                    </Grid>
                </Grid>
            </Container>
        </div>
    );
}

export default memo(Footer);