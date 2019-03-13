class AddSerieToLocal < ActiveRecord::Migration
  def change
    add_column :locals, :serie, :string
  end
end
