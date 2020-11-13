import React, {memo} from "react";
import List from "@material-ui/core/List";
import ListItem from "@material-ui/core/ListItem";
import ListItemText from "@material-ui/core/ListItemText";
import ListItemSecondaryAction from "@material-ui/core/ListItemSecondaryAction";
import {Switch} from "@material-ui/core";


const ShopBrandMemo = (props) => {

    const { list, values, onChange } = props;

    return(
        <div className={'shop-aside-partition'}>
            <h4>برندها</h4>
            <List>
                {list.map((b, i) => {
                    return (
                        <ListItem key={i}>
                            <ListItemText primary={b.title} />
                            <ListItemSecondaryAction>
                                <Switch
                                    name="brands"
                                    color="secondary"
                                    edge="end"
                                    checked={values.includes(b.id)}
                                    value={b.id}
                                    onChange={onChange}
                                />
                            </ListItemSecondaryAction>
                        </ListItem>
                    );
                })}
            </List>
        </div>
    );
}

export default memo(ShopBrandMemo);
