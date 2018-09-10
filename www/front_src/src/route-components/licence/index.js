import React from "react";


const tableBody = [{
    id: "1",
    checked: true,
    name: "Central Map Web Client",
    description: "Central Map Web Client lorem ipsum",
    version: "18.9.0",
    author: "Centreon Team",
    expiration: "17.05.2019",
    installed : true
  },
  {
    id: "2",
    checked: false,
    name: "Central Business Activity Monitoring",
    description: "Central Business Activity Monitoring lorem ipsum",
    version: "18.9.0",
    author: "Centreon",
    expiration: "17.05.2019",
    installed : false
  },
  {
    id: "3",
    checked: true,
    name: "Central Map Web Client",
    description: "Central Map Web Client lorem ipsum",
    version: "18.9.0",
    author: "Centreon Team",
    expiration: "17.05.2019",
    installed : true
  }];

  
class TableRow extends React.Component {
  render() {
    const {
      data
    } = this.props;
    const row = data.map((data) =>
     <tr>
        <td key={data.checked}>{data.checked === true ? "yes": "no"}</td>
        <td key={data.name}>{data.name}</td>
        <td key={data.description}>{data.description}</td>
        <td key={data.version}>{data.version}</td>
        <td key={data.author}>{data.author}</td>
        <td key={data.expiration}>{data.expiration}</td>
        <td key={data.installed}>{data.installed === true ? "y": "n"}</td>
      </tr>
    );
    return (
      <tbody>{row}</tbody>
    );
  }
}

class Table extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <table>
        <thead>
            <tr>
              <td>Checked</td>
              <td>Name</td>
              <td>Description</td>
              <td>Version</td>
              <td>Author</td>
              <td>Expiration</td>
              <td>Installed</td>
            </tr>
        </thead>
        <TableRow data={this.props.data} />
      </table>
    );
  }
  
}


export default Table 


