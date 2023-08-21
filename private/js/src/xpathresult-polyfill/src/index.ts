interface XPathResult {
    [Symbol.iterator](): any;
}

if(!(Symbol.iterator in XPathResult.prototype)) (XPathResult.prototype as XPathResult)[Symbol.iterator] = function*() {
    switch(this.resultType) {
        case XPathResult.NUMBER_TYPE:
            yield this.numberValue;
            break;
        case XPathResult.STRING_TYPE:
            yield this.stringValue;
            break;
        case XPathResult.BOOLEAN_TYPE:
            yield this.booleanValue;
            break;
        case XPathResult.UNORDERED_NODE_ITERATOR_TYPE:
        case XPathResult.ORDERED_NODE_ITERATOR_TYPE:
            var node;
            while(node = this.iterateNext()) yield node;
            break;
        case XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE:
        case XPathResult.ORDERED_NODE_SNAPSHOT_TYPE:
            var i;
            for(i=0; i<this.snapshotLength; i++) yield this.snapshotItem(i);
            break;
        case XPathResult.ANY_UNORDERED_NODE_TYPE:
        case XPathResult.FIRST_ORDERED_NODE_TYPE:
            yield this.singleNodeValue;
            break;
        default:
            console.error("Unable to iterate unknown XPathResult resultType: " + this.resultType);
            break;
    }
}