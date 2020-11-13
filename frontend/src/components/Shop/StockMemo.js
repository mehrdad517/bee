import React, { memo} from "react";
import List from "@material-ui/core/List";
import ListItem from "@material-ui/core/ListItem";
import ListItemText from "@material-ui/core/ListItemText";
import ListItemSecondaryAction from "@material-ui/core/ListItemSecondaryAction";
import {Switch} from "@material-ui/core";
import Typography from "@material-ui/core/Typography";


const ShopStockMemo = (props) => {

    const { onChange, selected } = props;

    return(
        <div>
            <List>
                <ListItem>
                    <ListItemText
                        id="switch-list-label-wifi"
                        primary={<Typography variant={"body2"}>کالاهای موجود</Typography>}
                    />
                    <ListItemSecondaryAction>
                        <Switch
                            color="secondary"
                            edge="end"
                            name="stock"
                            value={selected === 1 ? 0 : 1}
                            checked={Boolean(selected)}
                            onChange={onChange}
                        />
                    </ListItemSecondaryAction>
                </ListItem>
            </List>
        </div>
    );
}

export default memo(ShopStockMemo);
