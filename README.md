# timeline
* init project 3h (refresh memory)
* calculator creation 2h
* tests creation 1h
* final tests preparing readme 2h

# api execution
```shell script
curl --location --request GET 'http://localhost:8080/jsonRpc' \
--header 'userName: api' \
--header 'password: qwerty' \
--header 'Content-Type: application/json' \
--header 'Cookie: PHPSESSID=iikbb9raohv03vvshaf3hgk98k' \
--data-raw '{ "jsonrpc":"2.0", "method": "calculate", "params": {"formula": "20+44*(2*4+3.0.5+(55-33)+1*2)+(2+2/3)"}, "id": 1}'
```

```shell script
curl --location --request GET 'http://localhost:8080/jsonRpc' \
--header 'userName: api' \
--header 'password: qwerty' \
--header 'Content-Type: application/json' \
--header 'Cookie: PHPSESSID=rahvg7q1p9qdnubu60ei1to9s2' \
--data-raw '{ "jsonrpc":"2.0", "method": "getLatest", "params": {}, "id": 1}'
```
```shell script
curl --location --request GET 'http://localhost:8080/jsonRpc' \
--header 'userName: api' \
--header 'password: qwerty' \
--header 'Content-Type: application/json' \
--header 'Cookie: PHPSESSID=mhgb289lhufqcu6ga7ipdllrnn' \
--data-raw '{ "jsonrpc":"2.0", "method": "getLatest", "params": {"limit":10}, "id": 1}'
```