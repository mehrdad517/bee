import React, {useState, memo, useEffect} from "react";
import Grid from '@material-ui/core/Grid';
import {ENV} from "../../config/env";
import './style.css';

const Gallery = (props) => {

    const {list} = props;
    const [index, setIndex] = useState(0);
    const [src, setSrc] = useState('');
    const [toggle, setToggle] = useState('none');

    useEffect(() => {
        let modal = document.querySelectorAll('.modal-gallery')
        window.addEventListener('click', (event) => {
            for (let i = 0; i < modal.length; i++) {
                if (event.target === modal[i]) {
                    setToggle('none')
                }
            }
        })
    }, []);

    const nextImg = () => {
        let allImg = list;
        if ((allImg.length - 1) > index) {
            let item = allImg[index + 1];
            setSrc(item.prefix + '/' + item.file);
            setIndex(index + 1);
        } else {
            let item = allImg[0];
            setSrc(item.prefix + '/' + item.file);
            setIndex(0);
        }
    };

    const prevImg = () => {
        let allImg = list;
        if (index > 0) {
            let item = allImg[index - 1] ;
            setSrc(item.prefix + '/' + item.file);
            setIndex(index - 1);
        } else {
            let item = allImg[allImg.length - 1];
            setSrc(item.prefix + '/' + item.file);
            setIndex(allImg.length - 1);
        }
    };

    return(
        <>
            <div style={{display: toggle}} className='modal-gallery'>
                <img src={src}/>
                <img src={(`${ENV.API['FETCH']}/static/img/arrow.png`)} onClick={() => {nextImg()}} className='next-img'/>
                <img style={{ transform: 'rotate(180deg)'}} src={(`${ENV.API['FETCH']}/static/img/arrow.png`)} onClick={() => {prevImg()}} className='prev-img'/>
            </div>
            <Grid container={true} spacing={1}>
                {
                    list.map((item, index) => {
                        return (
                            <Grid item xs={6} sm={3} md={6} lg={6}>
                                <img alt={item.caption} className='gallery-img' onClick={() => {
                                    setSrc(item.prefix + '/' + item.file);
                                    setIndex(index);
                                    setToggle('flex');
                                }}  src={item.prefix + '/200/' + item.file}
                                />
                            </Grid>
                        )
                    })
                }
            </Grid>
        </>
    );
};


export default memo(Gallery);
