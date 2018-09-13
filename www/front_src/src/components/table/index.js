import React, { Component } from "react";

class TableComponent extends Component {
  render() {
    //static elements ::
    //button install
    const stateInstall = (
     <a href="">Install</a>
    ),
    //icon set when installed
    stateInstalled = (
      <div className="buttons-set">
        <span className="info-installed">ok</span>
        <a className="upload-licence" href="">U</a>
        <a className="download-licence" href="">D</a>
        <span className="remove">-</span>
      </div>
    ),
    //checkbox select all
   checkboxALl = (
      <React.Fragment>
        <input className="checkbox" type="checkbox" id="checkall" name="selectall"
              value="selectall"/>
        <label className="input-control-label" for="checkall">All</label>
      </React.Fragment>
    ),
    //configuration
    { configuration, data } = this.props;

    //render
    return (
      <table className="green">
        <caption>Licence list</caption>
        <thead>
          <tr>
            {configuration ? configuration.map(
              ({ label }) => (
                  <th scope="col">{label === 'All' ? checkboxALl : label }</th>
              )
            ) : null}
          </tr>
        </thead>
        <tbody>
          {
            data ? data.map((row, index) => (
              <tr key={index}>
                {configuration ? configuration.map(
                  ({ key, type }) => {
                    let column = null;
                    const checkboxSwitcher =  <React.Fragment>
                                                <input className="checkbox" type="checkbox" id={'chec'+row.id} name="checkbox"
                                                      value="selectall"/>
                                                <label className="input-control-label" for={'chec'+row.id}></label>
                                              </React.Fragment>,
                    singlecol = row[key];
                    switch (type) {
                      case 'boolcheckbox':
                        column = (<td>{checkboxSwitcher}</td>);
                        break;
                      case 'bool':
                        column = (<td>{singlecol ? stateInstalled : stateInstall }</td>);
                        break;
                      case 'text':
                        column = (<td>{singlecol}</td>);
                        break;
                      default:
                        column = (<td>{singlecol}</td>);
                    }
                    return column;
                  }
                )
                : null}
              </tr>
            )
            )
           : null}
        </tbody>
      </table>
    )
  }
}

export default TableComponent;