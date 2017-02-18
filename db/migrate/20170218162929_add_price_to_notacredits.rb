class AddPriceToNotacredits < ActiveRecord::Migration
  def change
    add_column :notacredits, :price, :float
  end
end
