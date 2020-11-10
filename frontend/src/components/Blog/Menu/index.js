import React, {memo} from 'react';
import {useDispatch, useSelector} from 'react-redux';
import './style.css';
import AddCircleOutlineOutlinedIcon from '@material-ui/icons/AddCircleOutlineOutlined';
import RemoveCircleOutlineOutlinedIcon from '@material-ui/icons/RemoveCircleOutlineOutlined';
import {Link} from 'react-router-dom';
import Divider from '@material-ui/core/Divider';
import {
    MENU_CHANGE_BLOG_EXPANDED, MENU_DRAWER
} from "../../../redux/types";
import Paper from "@material-ui/core/Paper";


export const BlogMenu = () => {

    const dispatch = useDispatch();

    const Menu = useSelector(state => state.menu);

    const handleExpand = (id, type) => {

        let expanded = [];

        let array_index = -1;

        switch (type) {
            case 'blog':
                expanded = Menu.blogExpanded;

                array_index = Menu.blogExpanded.indexOf(id);

                if (array_index > -1) {
                    Menu.blogExpanded.splice(array_index, 1);
                } else {
                    expanded.push(id);
                }

                dispatch({
                    type: MENU_CHANGE_BLOG_EXPANDED,
                    payload: expanded
                });
                break;
            default:
                break;
        }
    }

    const renderBlogMenu = (nodes) => {
        // nodes mapping
        const treeNodes = nodes.map((item, key) => {
            // id id node
            const { id } = item;
            // title title node
            const { title } = item;

            // slug
            const { slug } = item;

            // check has child
            const hasChild = item.children.length > 0;

            // check has child is true fetch children
            const children = hasChild ? renderBlogMenu(item.children) : '';

            return (
                <li key={key} className="tree-box">
          <span
              className={
                  hasChild === true
                      ? 'tree-parent has-child'
                      : 'tree-parent  has-no-child'
              }
          >
            <span onClick={() => handleExpand(id, 'blog')}>
              {hasChild === true &&
              (Menu.blogExpanded &&
              Menu.blogExpanded.includes(id) ? (
                  <RemoveCircleOutlineOutlinedIcon
                      color="action"
                      fontSize="small"
                  />
              ) : (
                  <AddCircleOutlineOutlinedIcon
                      color="action"
                      fontSize="small"
                  />
              ))}
            </span>
            <Link onClick={() => setTimeout(() => {dispatch({type: MENU_DRAWER, payload: false})}, 100) } to={`/shop/category/${id}/${slug}`}>{title}</Link>
          </span>
                    {hasChild === true && (
                        <ul
                            className="tree-children"
                            style={{
                                display:
                                    Menu.blogExpanded &&
                                    Menu.blogExpanded.includes(id)
                                        ? 'block'
                                        : 'none'
                            }}
                        >
                            {children}
                        </ul>
                    )}
                </li>
            );
        });

        return treeNodes;
    }


    return (
            <React.Fragment>
                {Menu.data.blog_categories.length > 0 && (
                    <Paper className={'blog_categories_container'}>
                        {renderBlogMenu(Menu.data.blog_categories)}
                    </Paper>
                )}
            </React.Fragment>
    );
};


export default memo(BlogMenu);
