import Chip from "@material-ui/core/Chip";
import Grid from "@material-ui/core/Grid";
import React, {memo} from "react";
import List from "@material-ui/core/List";
import ListItem from "@material-ui/core/ListItem";
import {Link} from "react-router-dom";
import ListItemText from "@material-ui/core/ListItemText";
import Breadcrumbs from '@material-ui/core/Breadcrumbs';
import NavigateNextIcon from '@material-ui/icons/NavigateBefore';
import Typography from '@material-ui/core/Typography';
import {Paper} from "@material-ui/core";
import {useLocation} from "react-router";

const ShopNavigationMemo = (props) => {

    const { list } = props;
    const location = useLocation();
    return(
        <Paper  className={'shop-navigation'}>
            <Breadcrumbs separator={<NavigateNextIcon color={"secondary"} fontSize="small" />} aria-label="breadcrumb">
                <Link to={'/'}><Typography variant={"body2"}>بی نتورک</Typography></Link>
                <Link to={'/shop'}><Typography variant={"body2"}>فروشگاه</Typography></Link>
                {list.map((nav, index) => {
                    return(
                        <Link to={ (location.pathname.match('category') ? '/category' : '/brand') + `/${nav.id}/${nav.slug}`}><Typography variant={"body2"}>{nav.title}</Typography></Link>
                    );
                })}
            </Breadcrumbs>
        </Paper>
    );
}

export default memo(ShopNavigationMemo);
