import Chip from "@material-ui/core/Chip";
import Grid from "@material-ui/core/Grid";
import React, {memo} from "react";
import List from "@material-ui/core/List";
import ListItem from "@material-ui/core/ListItem";
import {Link} from "react-router-dom";
import ListItemText from "@material-ui/core/ListItemText";


const ShopCategoryMemo = (props) => {

    const { list } = props;

    return(
        <div className={'shop-aside-partition'}>
            <h4>دسته بندی محصولات</h4>
            <List>
                {list.map((tree, index) => {
                    return(
                        <ListItem component={Link} to={`/category/${tree.id}/${tree.slug}`} className={'pointer'}  key={index}>
                            <ListItemText primary={tree.title} />
                        </ListItem>
                    );
                })}
            </List>
        </div>
    );
}

export default memo(ShopCategoryMemo);
