import React, { memo } from 'react';
import Grid from '@material-ui/core/Grid';
import PostBox from "../PostBox";

const PostList = memo(({ list }) => (


    <Grid spacing={1} container>
        {list.map((item, index) => {
            if (index > 3) return;
            return (
                <Grid item key={index} xs={12} sm={12} md={6}>
                    <PostBox key={index} item={item} />
                </Grid>
            );
        })}
    </Grid>
));

export default PostList;