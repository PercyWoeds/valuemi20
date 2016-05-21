json.array!(@clients) do |client|
  json.extract! client, :id, :vcodigo, :vruc, :vrazon2, :vdireccion, :vdistrito, :vprov, :vdep
  json.url client_url(client, format: :json)
end
