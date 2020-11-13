import React, {memo} from "react";
import {Link} from "react-router-dom";
import Breadcrumbs from '@material-ui/core/Breadcrumbs';
import NavigateNextIcon from '@material-ui/icons/NavigateBefore';
import Typography from '@material-ui/core/Typography';
import {Paper} from "@material-ui/core";


const ProductNavigationMemo = (props) => {

    const { list, current } = props;

    return(
        <Paper  className={'shop-navigation'}>
            <Breadcrumbs separator={<NavigateNextIcon color={"secondary"} fontSize="small" />} aria-label="breadcrumb">
                <Link to={'/'}><Typography variant={"body2"}>بی نتورک</Typography></Link>
                <Link to={'/shop'}><Typography variant={"body2"}>فروشگاه</Typography></Link>
                {list.map((nav, index) => {
                    return(
                        <Link to={`/category/${nav.id}/${nav.slug}`}><Typography variant={"body2"}>{nav.title}</Typography></Link>
                    );
                })}
                {current && <span style={{ color: '#d3d3d3'}}>{current}</span>}
            </Breadcrumbs>
        </Paper>
    );
}

export default memo(ProductNavigationMemo);
