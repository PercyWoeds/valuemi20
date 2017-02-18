class AddTipoToCredit < ActiveRecord::Migration
  def change
    add_column :credits, :tipo, :string
  end
end
