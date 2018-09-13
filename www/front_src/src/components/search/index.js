import React, { Component } from "react";

class SearchComponent extends Component {
  render() {
    return (
      <form role="search">
        <div class="search-control">
          <input type="search" id="site-search" name="search"
          placeholder="Search the keyword..."
          aria-label="Search through keyword content" />
          <button>Search</button>
        </div>
      </form>
      
    )
  }
}

export default SearchComponent;